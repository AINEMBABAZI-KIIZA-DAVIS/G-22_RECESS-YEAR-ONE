import os
os.environ["OMP_NUM_THREADS"] = "1"
import pandas as pd
from sqlalchemy import create_engine
from sklearn.cluster import KMeans
from sklearn.preprocessing import StandardScaler
import seaborn as sns
import matplotlib.pyplot as plt

# --------------------- CONFIGURE THIS SECTION ---------------------
db_user = 'root'
db_password = ''  # Leave empty if you're not using a password
db_host = 'localhost'
db_name = 'bakery_database'  # Change if your database name is different
sales_table = 'sales'  # This table must contain: customer_id, date_of_sale, total_amount
# ------------------------------------------------------------------

# ✅ Create database connection
engine = create_engine(f'mysql+pymysql://{db_user}:{db_password}@{db_host}/{db_name}')

# ✅ Load customer transaction data
query = f"""
    SELECT customer_id, date_of_sale AS order_date, total_amount AS revenue
    FROM {sales_table}
    WHERE customer_id IS NOT NULL
"""
df = pd.read_sql(query, engine)

# ✅ Check data before processing
if df.empty:
    raise ValueError("No sales data found. Ensure the 'sales' table has records.")

# ✅ Convert order_date to datetime
df['order_date'] = pd.to_datetime(df['order_date'])

# ✅ Compute RFM metrics
snapshot_date = df['order_date'].max() + pd.Timedelta(days=1)
rfm = df.groupby('customer_id').agg({
    'order_date': lambda x: (snapshot_date - x.max()).days,
    'customer_id': 'count',
    'revenue': 'sum'
})
rfm.columns = ['Recency', 'Frequency', 'Monetary']

# ✅ Normalize values for clustering
scaler = StandardScaler()
rfm_scaled = scaler.fit_transform(rfm)

# ✅ Apply KMeans clustering
kmeans = KMeans(n_clusters=4, random_state=42)
rfm['Cluster'] = kmeans.fit_predict(rfm_scaled)

# ✅ Optional: View clusters
print(rfm.reset_index().head())

# ✅ Visualize customer segments
sns.pairplot(rfm.reset_index(), hue='Cluster', palette='tab10')
plt.suptitle('Customer Segmentation (RFM)', y=1.02)
plt.show()
