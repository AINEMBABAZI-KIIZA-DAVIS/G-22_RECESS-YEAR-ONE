import os
os.environ["OMP_NUM_THREADS"] = "1"

import pandas as pd
import matplotlib.pyplot as plt
import seaborn as sns
from sqlalchemy import create_engine
from sklearn.cluster import KMeans
from sklearn.preprocessing import StandardScaler

# --------------------- CONFIGURE THIS SECTION ---------------------
db_user = 'root'
db_password = ''  # Leave empty if you're not using a password
db_host = 'localhost'
db_name = 'bakery_database'
sales_table = 'sales'  # Must contain: customer_id, date_of_sale, total_amount
# ------------------------------------------------------------------

# ✅ Create database connection
engine = create_engine(f'mysql+pymysql://{db_user}:{db_password}@{db_host}/{db_name}')

# ✅ Load and prepare RFM model once
def prepare_rfm_model(plot=False, save_plot=False):
    query = f"""
        SELECT customer_id, date_of_sale AS order_date, total_amount AS revenue
        FROM {sales_table}
        WHERE customer_id IS NOT NULL
    """
    df = pd.read_sql(query, engine)

    if df.empty:
        raise ValueError("No sales data found. Ensure the 'sales' table has records.")

    df['order_date'] = pd.to_datetime(df['order_date'])

    snapshot_date = df['order_date'].max() + pd.Timedelta(days=1)
    rfm = df.groupby('customer_id').agg({
        'order_date': lambda x: (snapshot_date - x.max()).days,
        'customer_id': 'count',
        'revenue': 'sum'
    })
    rfm.columns = ['Recency', 'Frequency', 'Monetary']

    scaler = StandardScaler()
    rfm_scaled = scaler.fit_transform(rfm)

    model = KMeans(n_clusters=4, random_state=42)
    rfm['Cluster'] = model.fit_predict(rfm_scaled)

    # ✅ Optional visualization
    if plot or save_plot:
        fig = sns.pairplot(rfm.reset_index(), hue='Cluster', palette='tab10')
        plt.suptitle('Customer Segmentation (RFM)', y=1.02)

        if save_plot:
            plt.savefig("static/customer_segments.png")

        if plot:
            plt.show()

    return model, scaler, rfm.columns

# Load model globally (once)
kmeans_model, scaler_model, rfm_features = prepare_rfm_model(plot=False, save_plot=False)

# ✅ Define logic for segmenting a customer
def segment_customer_logic(data):
    """
    Expects `data` as a dict with keys:
    - Recency
    - Frequency
    - Monetary
    """
    try:
        values = [[
            data['Recency'],
            data['Frequency'],
            data['Monetary']
        ]]
    except KeyError as e:
        raise ValueError(f"Missing field in input data: {e}")

    scaled = scaler_model.transform(values)
    cluster = kmeans_model.predict(scaled)[0]
    return f"Cluster {cluster}"  # or return cluster if you prefer numeric ID

# ✅ For testing/standalone run
if __name__ == "__main__":
    # Regenerate RFM and visualize when run directly
    prepare_rfm_model(plot=True, save_plot=False)
