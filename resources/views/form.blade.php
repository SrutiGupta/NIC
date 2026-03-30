<!DOCTYPE html>
<html>
<head>
    <title>NIC Form</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f7fa;
        }

        .container {
            width: 500px;
            margin: 50px auto;
            background: #ffffff;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }

        label {
            font-weight: bold;
            display: block;
            margin-top: 10px;
        }

        input, textarea {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        textarea {
            resize: none;
            height: 100px;
        }

        button {
            width: 100%;
            margin-top: 20px;
            padding: 12px;
            background-color: #0d6efd;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
        }

        button:hover {
            background-color: #084298;
        }

        .small-btn {
            padding: 8px 12px;
            background-color: #6c757d;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 13px;
            cursor: pointer;
            width: auto;
        }

        .small-btn:hover {
            background-color: #5a6268;
        }

        /* Loader */
        .loader {
            display: none;
            text-align: center;
            margin-top: 10px;
        }

        .spinner {
            border: 4px solid #f3f3f3;
            border-top: 4px solid #0d6efd;
            border-radius: 50%;
            width: 25px;
            height: 25px;
            animation: spin 1s linear infinite;
            margin: auto;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Success */
        .success {
            background: #d1e7dd;
            color: #0f5132;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 10px;
            text-align: center;
        }

        /* Preview */
        #preview {
            display: none;
            width: 150px;
            margin-top: 10px;
            border-radius: 8px;
            border: 1px solid #ccc;
        }
    </style>
</head>

<body>

<div class="container">
    <h2>SIMPLE FORM NIC</h2>

    @if(session('success'))
        <div class="success">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div style="background:#f8d7da; color:#842029; padding:10px; border-radius:5px; margin-bottom:10px;">
            @foreach($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <form id="form" method="POST" action="/submit" enctype="multipart/form-data">
    @csrf

    <label>Full Name</label>
    <input type="text" name="name" value="{{ old('name') }}" placeholder="Enter your full name">

    <label>Address</label>
    <input type="text" name="address" value="{{ old('address') }}" placeholder="Enter your address">

    <label>Email</label>
    <input type="email" name="email" value="{{ old('email') }}" placeholder="Enter your email">

    <label>Phone Number</label>
    <input type="text" name="phone" value="{{ old('phone') }}" placeholder="Enter phone number">

    <label>Upload Image</label>

    <div style="display:flex; gap:10px; align-items:center;">
        <input type="file" id="fileInput" name="image" accept="image/*">
        <button type="button" onclick="openCamera()" class="small-btn">Take Photo</button>
    </div>

    <!-- ✅ PREVIEW BELOW (clean UI) -->
    <img id="preview">

    <video id="video" width="200" autoplay style="display:none; margin-top:10px;"></video>

    <button type="button" onclick="capturePhoto()" id="captureBtn" class="small-btn" style="display:none;">
        Capture
    </button>

    <canvas id="canvas" style="display:none;"></canvas>

    <label>Message</label>
    <textarea name="message" placeholder="Enter your message">{{ old('message') }}</textarea>

    <button type="submit" id="submitBtn">Submit</button>

    <div class="loader" id="loader">
        <div class="spinner"></div>
        <p>Submitting...</p>
    </div>

</form>
</div>

<script>
let stream;

// 📁 FILE PREVIEW
document.getElementById('fileInput').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const preview = document.getElementById('preview');
        preview.src = URL.createObjectURL(file);
        preview.style.display = 'block';
    }
});

// 🎥 OPEN CAMERA
function openCamera() {
    const video = document.getElementById('video');
    const captureBtn = document.getElementById('captureBtn');

    navigator.mediaDevices.getUserMedia({ video: true })
        .then(s => {
            stream = s;
            video.srcObject = stream;
            video.style.display = 'block';
            captureBtn.style.display = 'inline-block';
        });
}

// 📸 CAPTURE PHOTO
function capturePhoto() {
    const video = document.getElementById('video');
    const canvas = document.getElementById('canvas');
    const preview = document.getElementById('preview');

    canvas.width = video.videoWidth;
    canvas.height = video.videoHeight;

    const ctx = canvas.getContext('2d');

    // small delay fixes black image issue
    setTimeout(() => {
        ctx.drawImage(video, 0, 0);

        const imageData = canvas.toDataURL('image/png');

        // show preview
        preview.src = imageData;
        preview.style.display = 'block';

        // convert to file
        fetch(imageData)
            .then(res => res.blob())
            .then(blob => {
                const file = new File([blob], "photo.png", { type: "image/png" });

                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(file);

                document.getElementById('fileInput').files = dataTransfer.files;
            });

        // stop camera
        stream.getTracks().forEach(track => track.stop());
        video.style.display = 'none';
        document.getElementById('captureBtn').style.display = 'none';

    }, 200);
}

// 🚀 LOADER
document.getElementById("form").addEventListener("submit", function() {
    document.getElementById("submitBtn").style.display = "none";
    document.getElementById("loader").style.display = "block";
});
</script>

</body>
</html>