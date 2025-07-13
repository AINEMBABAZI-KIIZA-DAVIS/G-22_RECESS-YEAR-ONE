from prophet import Prophet
from utils.data_loader import load_table
import pandas as pd

def predict_demand(product_name: int, days: int = 30) -> float:
    df = load_table("top_products", where_clause=f"product_name = '{product_name}'")

    if df.empty:
        raise ValueError("No sales data found for this product.")

    # Ensure correct columns exist for Prophet
    if not {"date_of_sale", "units_sold"}.issubset(df.columns):
        raise ValueError('Dataframe must have "date_of_sale" and "units_sold" columns.')

    df = df.rename(columns={
        "date_of_sale": "ds",
        "units_sold": "y"
    })

    model = Prophet()
    model.fit(df)

    future = model.make_future_dataframe(periods=days)
    forecast = model.predict(future)

    return round(forecast['yhat'].iloc[-1], 2)


if __name__ == "__main__":
    try:
        product_name = "Cookie"  # change to your product ID as needed
        days = 30       # forecast period in days
        prediction = predict_demand(product_name, days)
        print(f"Predicted demand for {product_name} in {days} days: {prediction}")
    except Exception as e:
        print(f"Error: {e}")
