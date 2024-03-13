import pandas as pd
import numpy as np
from datetime import datetime, timedelta

# Number of samples
num_samples = 500

# Generate a list of timestamps from now, going back by 1 minute for each sample
timestamps = [datetime.now() - timedelta(minutes=i) for i in range(num_samples)]
# Convert timestamps to Unix timestamp format (in seconds)
device_timestamps = [int(ts.timestamp()) for ts in timestamps]

# Generate a list of random values between -10 and 10 for each sample
valts = np.random.randint(-10, 11, num_samples)

# Create a DataFrame
df = pd.DataFrame({
    'device_timestamps': device_timestamps,
    'valts': valts
})

# Save to CSV
csv_filename = 'sample_data.csv'  # Specify your desired path if needed
df.to_csv(csv_filename, index=False)

print(f"CSV file has been saved as {csv_filename}.")
