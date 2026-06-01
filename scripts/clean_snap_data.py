"""
clean_snap_data.py

Transforms the raw USDA SNAP authorized-retailer file for New York State
into the clean New York City dataset used by this project.

WHAT THIS DOES (in order):
  1. Reads the raw USDA file (statewide, all store types, ~17,000 rows).
  2. Filters to the five NYC boroughs using the County field.
  3. Adds a clean "borough" label derived from the county.
  4. Keeps only the store types relevant to trafficking risk analysis
     (convenience, grocery, specialty, and "other") -- drops supermarkets,
     superstores, farmers markets, and restaurant-meal programs, which
     research shows traffic at negligible rates.
  5. Renames columns to clean, database-friendly snake_case.
  6. Strips stray whitespace from text fields.
  7. Drops columns the project doesn't use (web-mercator X/Y, etc.).
  8. Writes the cleaned result to a CSV ready for the Laravel seeder.

WHAT THIS DOES NOT DO:
  This script does not fetch data from the USDA. Getting the raw file is a
  manual step: download the New York State authorized-retailer data from the
  USDA FNS ArcGIS hub, then point this script at it. The script only handles
  the transformation, so the process is repeatable: drop in a fresh raw file,
  run this, get a fresh clean file.

USAGE:
  python clean_snap_data.py <raw_input_file> <clean_output_file>

  Example:
  python clean_snap_data.py SNAP_NewYork.xlsx nyc_snap_retailers.csv

SOURCE OF RAW DATA:
  USDA FNS SNAP Retailer Locator / ArcGIS data hub
  https://usda-snap-retailers-usda-fns.hub.arcgis.com
"""

import sys
import pandas as pd

# County name (as it appears in the raw USDA data) -> borough label
BOROUGH_MAP = {
    "KINGS": "Brooklyn",
    "BRONX": "Bronx",
    "NEW YORK": "Manhattan",
    "QUEENS": "Queens",
    "RICHMOND": "Staten Island",
}

# Store types kept for trafficking-risk analysis. Small-format stores account
# for the overwhelming majority of SNAP trafficking; large-format stores
# (supermarkets, superstores) and markets/meal programs are dropped.
KEEP_STORE_TYPES = [
    "Convenience Store",
    "Grocery Store",
    "Specialty Store",
    "Other",
]

# Raw column name -> clean snake_case name
COLUMN_RENAME = {
    "Record_ID": "fns_record_id",
    "Store_Name": "store_name",
    "Store_Street_Address": "street_address",
    "City": "city",
    "Store_Type": "store_type",
    "Zip_Code": "zip_code",
    "County": "county",
    "State": "state",
    "Latitude": "latitude",
    "Longitude": "longitude",
}

# Final column order written to the output file
OUTPUT_COLUMNS = [
    "fns_record_id",
    "store_name",
    "store_type",
    "street_address",
    "city",
    "borough",
    "zip_code",
    "county",
    "state",
    "latitude",
    "longitude",
]

TEXT_FIELDS = [
    "store_name", "street_address", "city", "borough",
    "county", "state", "store_type",
]


def clean(raw_path: str, out_path: str) -> None:
    # 1. Read raw file (handles .xlsx or .csv)
    if raw_path.lower().endswith(".csv"):
        df = pd.read_csv(raw_path)
    else:
        df = pd.read_excel(raw_path)
    print(f"Raw rows read: {len(df)}")

    # 2. Filter to the five NYC boroughs by county
    county_clean = df["County"].astype(str).str.upper().str.strip()
    nyc = df[county_clean.isin(BOROUGH_MAP.keys())].copy()
    print(f"Rows after NYC filter: {len(nyc)}")

    # 3. Add clean borough label
    nyc["borough"] = county_clean[county_clean.isin(BOROUGH_MAP.keys())].map(BOROUGH_MAP)

    # 4. Keep only relevant store types
    nyc = nyc[nyc["Store_Type"].isin(KEEP_STORE_TYPES)].copy()
    print(f"Rows after store-type filter: {len(nyc)}")

    # 5. Rename columns to snake_case
    nyc = nyc.rename(columns=COLUMN_RENAME)

    # 6. Strip whitespace from text fields
    for col in TEXT_FIELDS:
        if col in nyc.columns:
            nyc[col] = nyc[col].astype(str).str.strip()

    # 7. Keep only the columns the project uses, in order
    nyc = nyc[OUTPUT_COLUMNS]

    # 8. Write clean output
    nyc.to_csv(out_path, index=False)
    print(f"Clean rows written: {len(nyc)}  ->  {out_path}")


if __name__ == "__main__":
    if len(sys.argv) != 3:
        print("Usage: python clean_snap_data.py <raw_input_file> <clean_output_file>")
        sys.exit(1)
    clean(sys.argv[1], sys.argv[2])
