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
        .post-title {
            color: #667eea;
            font-size: 22px;
            font-weight: bold;
            margin: 20px 0 10px 0;
        }
        .post-author {
            color: #666;
            font-size: 14px;
            margin-bottom: 20px;
        }
        .post-body {
            color: #444;
            line-height: 1.8;
            margin: 20px 0;
            white-space: pre-wrap;
            word-wrap: break-word;
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
        .divider {
            border-top: 2px solid #667eea;
            margin: 20px 0;
        }
        .author-info {
            background-color: #f9f9f9;
            padding: 15px;
            border-radius: 4px;
            margin: 15px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📝 New Post from {{ $author->username }}</h1>
        </div>

        <div class="content">
            <p>Hello,</p>
            
            <p>{{ $author->username }} has published a new post on FirstApp!</p>

            <div class="author-info">
                <strong>Author:</strong> {{ $author->username }}<br>
                <strong>Posted:</strong> {{ $post->created_at->format('M d, Y \a\t g:i A') }}
            </div>

            <div class="post-title">{{ $post->title }}</div>

            <div class="post-body">{{ $post->body }}</div>

            <div class="divider"></div>

            <a href="{{ route('post.show', $post->id) }}" class="cta-button">View Full Post</a>

            <p style="color: #999; font-size: 14px; margin-top: 30px;">
                You're receiving this email because you follow {{ $author->username }} on FirstApp.
            </p>
        </div>

        <div class="footer">
            <p>&copy; {{ date('Y') }} FirstApp. All rights reserved.</p>
            <p><a href="{{ route('home') }}" style="color: #667eea; text-decoration: none;">Visit FirstApp</a></p>
        </div>
    </div>
</body>
</html>
