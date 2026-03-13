import pandas as pd
import matplotlib.pyplot as plt
from statsmodels.tsa.arima.model import ARIMA
from statsmodels.tsa.stattools import adfuller
from sklearn.metrics import mean_absolute_error

# Load the dataset
file_path = 'Dataset_Forecasting_ARIMA_Lengkap.xlsx'
dataset = pd.read_excel(file_path)

# Convert 'Date' column to datetime format and set as index
dataset['Date'] = pd.to_datetime(dataset['Date'])
dataset.set_index('Date', inplace=True)

# Check if there are missing dates and fill them
complete_date_range = pd.date_range(start=dataset.index.min(), end=dataset.index.max(), freq='D')
dataset_reindexed = dataset.reindex(complete_date_range)

# Fill missing values using forward fill
dataset_reindexed['Total_Sales'] = dataset_reindexed['Total_Sales'].ffill()

# Perform Augmented Dickey-Fuller (ADF) test to check if the time series is stationary
adf_test = adfuller(dataset_reindexed['Total_Sales'])
adf_statistic, p_value, used_lag, n_observations, critical_values, icbest = adf_test

if p_value < 0.05:
    print('The time series is stationary, no differencing required.')
else:
    print('The time series is not stationary, differencing may be needed.')

# Split data into training and test sets (90% for training, 10% for testing)
train_size = int(len(dataset_reindexed) * 0.9)
train_data, test_data = dataset_reindexed['Total_Sales'][:train_size], dataset_reindexed['Total_Sales'][train_size:]

# Fit ARIMA model (initial parameters: p=1, d=0, q=1)
model = ARIMA(train_data, order=(1, 0, 1))
fitted_model = model.fit()

# Forecast for the test period
forecast = fitted_model.forecast(steps=len(test_data))

# Plot the actual vs forecasted values
plt.figure(figsize=(10, 6))
plt.plot(train_data.index, train_data, label='Training Data')
plt.plot(test_data.index, test_data, label='Actual Test Data', color='orange')
plt.plot(test_data.index, forecast, label='Forecasted Data', linestyle='--', color='green')
plt.title('ARIMA Forecast for Total Sales (Predicted vs Actual)')
plt.xlabel('Date')
plt.ylabel('Total Sales')
plt.legend()
plt.show()

# Calculate the Mean Absolute Error (MAE) between the actual and forecasted values
mae = mean_absolute_error(test_data, forecast)
print(f'Mean Absolute Error (MAE) between actual and forecasted sales: {mae:.2f}')

# Save the forecasted data to a CSV file
forecasted_data = pd.DataFrame(forecast, index=test_data.index, columns=['Forecasted Sales'])
forecasted_data.to_csv('forecasted_sales_analysis.csv')
