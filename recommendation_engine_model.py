import pandas as pd
from sqlalchemy import create_engine
import pymysql
from surprise import Dataset, Reader, SVD
from surprise.model_selection import train_test_split
from surprise.accuracy import rmse

# ✅ Step 1: Database config
db_config = {
    'host': 'localhost',
    'user': 'root',
    'password': '',
    'database': 'bakery_database'
}
mysql_url = f"mysql+pymysql://{db_config['user']}:{db_config['password']}@{db_config['host']}/{db_config['database']}"
engine = create_engine(mysql_url)

# ✅ Step 2: Load sales data and compute ratings
sales_query = "SELECT customer_id, product_id, units_sold FROM sales"
df_sales = pd.read_sql(sales_query, engine)

# Group and normalize to create ratings
ratings_df = df_sales.groupby(['customer_id', 'product_id'])['units_sold'].sum().reset_index()
ratings_df['rating'] = (ratings_df['units_sold'] / ratings_df['units_sold'].max()) * 5
ratings_df['rating'] = ratings_df['rating'].round(2)

# Save to new or existing ratings table
ratings_df[['customer_id', 'product_id', 'rating']].to_sql(
    "customer_product_ratings", con=engine, index=False, if_exists="replace"
)

# ✅ Step 3: Load ratings into Surprise
query = "SELECT customer_id, product_id, rating FROM customer_product_ratings"
df = pd.read_sql(query, engine)

reader = Reader(rating_scale=(1, 5))
data = Dataset.load_from_df(df[['customer_id', 'product_id', 'rating']], reader)

# ✅ Step 4: Train/test split
trainset, testset = train_test_split(data, test_size=0.2, random_state=42)

# ✅ Step 5: Train model
model = SVD()
model.fit(trainset)

# ✅ Step 6: Evaluate
predictions = model.test(testset)
print("\n📊 RMSE (Root Mean Squared Error):", round(rmse(predictions), 4))

# ✅ Step 7: Generate recommendations
def get_recommendations(user_id, top_n=3):
    # Removed debug prints for cleaner output

    product_ids = df['product_id'].unique()

    already_rated = df[df['customer_id'] == user_id]['product_id'].values

    predictions = []
    for pid in product_ids:
        if pid not in already_rated:
            try:
                est = model.predict(user_id, pid).est
                predictions.append((pid, est))
            except Exception as e:
                # Optional: handle prediction errors silently or log them differently
                pass

    top_preds = sorted(predictions, key=lambda x: x[1], reverse=True)[:top_n]

    top_product_ids = [pid for pid, _ in top_preds]

    if top_product_ids:
        ids_str = ",".join(map(str, top_product_ids))
        names_query = f"""
            SELECT id AS product_id, name AS product_name
            FROM products
            WHERE id IN ({ids_str})
        """
        product_names_df = pd.read_sql(names_query, engine)

        print(f"\n🎯 Top {top_n} Recommended Products for Customer {user_id}:\n")
        print(product_names_df.to_string(index=False))

        return product_names_df
    else:
        print(f"No recommendations for user {user_id}")
        return pd.DataFrame(columns=['product_id', 'product_name'])


# Example usage:
customer_id = 1
top_n = 3
recommended_products = get_recommendations(customer_id, top_n)
