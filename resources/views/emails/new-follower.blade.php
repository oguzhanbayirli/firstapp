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
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .content {
            padding: 30px;
        }
        .follower-card {
            background-color: #f9f9f9;
            border: 2px solid #667eea;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            margin: 20px 0;
        }
        .follower-avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
            margin: 0 auto 15px;
            border: 3px solid #667eea;
        }
        .follower-name {
            font-size: 20px;
            font-weight: bold;
            color: #333;
            margin: 10px 0;
        }
        .follower-username {
            color: #667eea;
            font-size: 16px;
            margin-bottom: 15px;
        }
        .cta-button {
            display: inline-block;
            background-color: #667eea;
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 4px;
            margin: 10px 5px;
            font-weight: bold;
        }
        .cta-button:hover {
            background-color: #764ba2;
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
            <h1>👤 New Follower!</h1>
        </div>

        <div class="content">
            <p>Hello {{ $followedUser->username }},</p>
            
            <p>Great news! Someone new is following you on FirstApp.</p>

            <div class="follower-card">
                <img src="{{ $follower->avatar }}" alt="{{ $follower->username }}" class="follower-avatar">
                <div class="follower-name">{{ $follower->username }}</div>
                <div class="follower-username">@{{ $follower->username }}</div>
                <a href="{{ route('profile.show', $follower->username) }}" class="cta-button">View Profile</a>
            </div>

            <p>Check out their profile to see what they've been posting!</p>

            <p style="color: #999; font-size: 14px; margin-top: 30px;">
                You're receiving this email because you have notifications enabled on FirstApp.
            </p>
        </div>

        <div class="footer">
            <p>&copy; {{ date('Y') }} FirstApp. All rights reserved.</p>
            <p><a href="{{ route('home') }}" style="color: #667eea; text-decoration: none;">Visit FirstApp</a></p>
        </div>
    </div>
</body>
</html>
