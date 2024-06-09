<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
</head>
<body style="font-family: 'Fira Sans Condensed', sans-serif; background-color: #4D5171; color: #ffffff; padding: 0; margin: 0; text-align: center;">
    <div style="background-color: #3f4259; margin: 20px auto; padding: 20px; border-radius: 10px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); width: 80%; max-width: 800px;">
        <div style="width: 100%; height: auto; border-bottom: 5px solid #3f4259; background-image: url('your-image-url-here.jpg'); height: 150px; background-size: cover; background-position: center;"></div>
        <h1 style="font-size: 24px;">Welcome {{$name}}!</h1>
        <p style="font-size: 16px; line-height: 1.5; text-align: left;">Thanks so much for joining the Oilsavings management team—we're thrilled to have you! Please click on the link below to complete your registration. Then, wait for the manager to activate your account.</p>
        <a href="{{$verificationLink}}" style="background-color: #ffc94c; border: none; border-radius: 20px; color: #3f4259; padding: 10px 20px; text-decoration: none; font-size: 16px; display: inline-block; margin: 20px 0;">Verify Email</a>
        <div style="height: 2px; background-color: lightsteelblue; width: 90%; margin: 10px auto;"></div>
        <div style="display: flex; justify-content: space-between;">
            <div style="flex: 1; padding: 10px; box-sizing: border-box;">
                <img style="width: 100%; height: auto;" src="https://encrypted-tbn1.gstatic.com/images?q=tbn:ANd9GcQmTxJJ80ElxjT2nYUOjVCtz3hg79lyL681QvlV5gvVWwst7RFC" alt="Car Image">
            </div>
            <div style="flex: 1; padding: 10px; box-sizing: border-box;">
                <p style="font-size: 16px; line-height: 1.5; text-align: left;">Welcome to the Oilsavings management team. Your role is crucial in ensuring that our platform operates efficiently and effectively. As an administrator, you have the responsibility to maintain order and integrity within our application. This involves a series of important tasks that will help provide the best possible experience for our users.</p>
            </div>
        </div>
    </div>
</body>
</html>
