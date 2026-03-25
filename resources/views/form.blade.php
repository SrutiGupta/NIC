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
    </style>
</head>

<body>

<div class="container">
    <h2>SIMPLE FORM NIC</h2>

    <form>
        <label>Full Name</label>
        <input type="text" name="name" placeholder="Enter your full name">

        <label>Address</label>
        <input type="text" name="address" placeholder="Enter your address">

        <label>Email</label>
        <input type="email" name="email" placeholder="Enter your email">

        <label>Phone Number</label>
        <input type="text" name="phone" placeholder="Enter phone number">

        <label>Message</label>
        <textarea name="message" placeholder="Enter your message"></textarea>

        <button type="submit">Submit</button>
    </form>
</div>

</body>
</html>