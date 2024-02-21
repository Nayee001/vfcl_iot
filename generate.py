import pandas as pd
import json
import random
from datetime import datetime

def csv_to_custom_json(csv_filepath, json_template, batch_size=100):
    # Read the CSV in chunks
    chunk_iterator = pd.read_csv(csv_filepath, chunksize=batch_size)

    for i, chunk in enumerate(chunk_iterator):
        # Prepare the data section from the chunk
        data_list = chunk.to_dict(orient='records')

        # Update the JSON template with the current chunk's data
        json_template['data'] = data_list

        # Randomly choose and update the status for the current batch
        json_template['fault_status'] = random.choice(['ON', 'OFF'])

        # Optionally, update the timestamp to the current time
        json_template['timestamp'] = datetime.now().isoformat()

        # Generate a JSON file for the current batch
        json_filename = f'output_batch_{i+1}.json'
        with open(json_filename, 'w') as json_file:
            json.dump(json_template, json_file, indent=4)
            
        print(f"Batch {i+1} saved as {json_filename} with status {json_template['fault_status']}.")

# Define your JSON template based on the provided structure
json_template = {
    "topic": "mqttdevice/",
    "fault_status": "",  # This will be updated dynamically with a random value
    "timestamp": "",  # This will be updated dynamically
    "device_status": "Active",
    "device_api": "4dfadf3277b6ee784",
    "health_status": "No Fault Occurs",
    "data": []  # This will be filled with data from the CSV
}
# Example usage
csv_filepath = 'sample_data.csv'  # Update this with your actual CSV file path
csv_to_custom_json(csv_filepath, json_template)
