from flask import Flask, request, jsonify
import os
import numpy as np
from keras.preprocessing.image import load_img, img_to_array
from keras.models import load_model
from typing import Tuple

# Initialize Flask app
app = Flask(__name__)

# Constants
IMG_SIZE = 224
MODEL_PATH = 'mobileNet_v2.h5'
CLASS_NAMES = ['Pantai', 'Wisata Buatan', 'Gunung', 'Alam', 'Pemandian', 'Air Terjun']
BASE_PATH = './blaspit/public/storage/filter/'  # Local folder path

# Load pre-trained model
model = load_model(MODEL_PATH)

def load_and_preprocess_image(image_path: str) -> np.ndarray:
    """
    Load and preprocess an image for prediction.
    Args:
        image_path (str): Path to the image file.
    Returns:
        np.ndarray: Preprocessed image ready for prediction.
    """
    img = load_img(image_path, target_size=(IMG_SIZE, IMG_SIZE))
    img_array = img_to_array(img)
    img_array = img_array.reshape(-1, IMG_SIZE, IMG_SIZE, 3)
    img_array = img_array.astype('float32') / 255.0
    return img_array

def get_image_from_local(image_url: str) -> Tuple[np.ndarray, str]:
    """
    Retrieve image from the local file system based on the image URL provided.
    Args:
        image_url (str): URL of the image.
    Returns:
        Tuple[np.ndarray, str]: Processed image and a possible error message.
    """
    try:
        # Construct the full file path
        image_path = os.path.join(BASE_PATH, image_url)
        
        if not os.path.isfile(image_path):
            return None, "Image not found at the provided path."
        
        # Preprocess and return the image
        return load_and_preprocess_image(image_path), None
    
    except Exception as e:
        return None, f"Failed to load image: {str(e)}"

@app.route('/predict', methods=['GET'])
def predict():
    """
    Handle the image prediction request.
    The image URL is passed as a query parameter and prediction is returned as a JSON response.
    Returns:
        jsonify: JSON response with the prediction result or error.
    """
    image_url = request.args.get('image_url')
    if not image_url:
        return jsonify({"error": "No image URL provided"}), 400

    # Retrieve and preprocess the image from the local path
    image, error = get_image_from_local(image_url)
    if error:
        return jsonify({"error": error}), 400

    # Make prediction using the pre-trained model
    result_probs = model.predict(image)
    result_index = np.argmax(result_probs)
    predicted_class = CLASS_NAMES[result_index]

    return jsonify({"prediction": predicted_class})

if __name__ == '__main__':
    app.run(debug=True)
