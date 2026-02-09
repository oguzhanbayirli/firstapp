<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin: 20px auto;
            max-width: 600px;
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 40px 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
            margin-bottom: 10px;
        }
        .header p {
            margin: 0;
            font-size: 16px;
            opacity: 0.9;
        }
        .content {
            padding: 30px;
        }
        .cta-button {
            display: inline-block;
            background-color: #667eea;
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 4px;
            margin: 20px 0;
            font-weight: bold;
            text-align: center;
        }
        .cta-button:hover {
            background-color: #764ba2;
        }
        .features {
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 4px;
            margin: 20px 0;
        }
        .feature-item {
            margin: 10px 0;
            padding-left: 25px;
            position: relative;
        }
        .feature-item:before {
            content: "✓";
            color: #667eea;
            font-weight: bold;
            position: absolute;
            left: 0;
        }
        .footer {
            background-color: #f9f9f9;
            color: #666;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            border-top: 1px solid #eee;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🎉 Welcome to FirstApp!</h1>
            <p>Happy to have you on board, {{ $user->username }}!</p>
        </div>

        <div class="content">
            <p>Hello {{ $user->username }},</p>
            
            <p>Thank you for creating your account on FirstApp! We're excited to have you join our community of users sharing their thoughts and connecting with others.</p>

            <div class="features">
                <h3 style="margin-top: 0;">Here's what you can do:</h3>
                <div class="feature-item">Create and share posts with the community</div>
                <div class="feature-item">Follow other users to see their posts</div>
                <div class="feature-item">Engage with the FirstApp community</div>
                <div class="feature-item">Customize your profile and avatar</div>
                <div class="feature-item">Chat in real-time with other users</div>
            </div>

            <p>Get started by completing your profile and making your first post!</p>

            <a href="{{ route('home') }}" class="cta-button">Start Exploring FirstApp</a>

            <p style="color: #999; font-size: 14px;">
                If you have any questions or need help, feel free to reach out to us!
            </p>
        </div>

        <div class="footer">
            <p>&copy; {{ date('Y') }} FirstApp. All rights reserved.</p>
            <p><a href="{{ route('home') }}" style="color: #667eea; text-decoration: none;">Visit FirstApp</a></p>
        </div>
    </div>
</body>
</html>
