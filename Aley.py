from flask import Flask, request, jsonify
import numpy as np
from keras.preprocessing.image import load_img, img_to_array
from keras.models import load_model
import requests
from io import BytesIO

app = Flask(__name__)

# Set the size and model path
IMG_SIZE = 224
MODEL_PATH = 'mobileNet_v2.h5'

# Load the model
model = load_model(MODEL_PATH)

class_names = ['Pantai', 'Wisata Buatan', 'Gunung', 'Alam', 'Pemandian', 'Air Terjun']

def load_and_preprocess_image(image):
    img = img_to_array(image)
    img = img.reshape(-1, IMG_SIZE, IMG_SIZE, 3)
    img = img.astype('float32')
    img = img / 255.0
    return img

@app.route('/predict', methods=['GET'])
def predict():
    image_url="https://www.lampungselatankab.go.id/web/wp-content/uploads/2022/06/20200611100724_air-terjun-675x380.jpg"
    print(image_url)
    if not image_url:
        return jsonify({"error": "No image URL provided"}), 400

    try:
        response = requests.get(image_url)
        if response.status_code != 200:
            return jsonify({"error": "Could not retrieve image from the URL"}), 400
        image = load_img(BytesIO(response.content), target_size=(IMG_SIZE, IMG_SIZE))
    except Exception as e:
        return jsonify({"error": f"Failed to load image: {str(e)}"}), 400

    # Preprocess the image and make a prediction
    img = load_and_preprocess_image(image)
    result_probs = model.predict(img)
    result_index = np.argmax(result_probs)
    predicted_class = class_names[result_index]

    # Return the result as JSON
    return jsonify({"prediction": predicted_class})

if __name__ == '__main__':
    app.run(debug=True)
