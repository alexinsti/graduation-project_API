<div style="font-family: Arial, sans-serif; padding: 20px; border: 1px solid #ccc; border-radius: 5px;">
    <h1 style="margin-bottom: 20px;">Content reported:</h1>

    @if($type == 1)
        <div style="background-color: #f9f9f9; padding: 10px; border-radius: 5px;">
            <p style="margin-bottom: 10px;">
                <strong>ID:</strong> {{$content->id}}<br>
                <strong>Location:</strong> {{$content->location}}<br>
            </p>
            <div style="background-image: url('data:image/gif;base64,{{$img_base_64}}');
                        background-repeat: no-repeat;
                        background-size: contain;
                        background-position: center;
                        width: 100%;
                        height: 300px;
                        border: 1px solid #ccc;
                        border-radius: 5px;
                        margin-bottom: 10px;">
            </div>
            <div style="display: flex; justify-content: space-between">
                <a href="{{ route('reset.picture', ['token' => $token]) }}" class="btn">Reset picture</a>
                <a href="{{ route('close.ticket', ['token' => $token]) }}" class="btn">Done</a>
            </div>
        </div>
    @endif

    @if($type == 2)
        <div style="background-color: #f9f9f9; padding: 10px; border-radius: 5px;">
            <p style="margin-bottom: 10px;">
                <strong>ID:</strong> {{$content->id}}<br>
                <strong>Name:</strong> {{$content->name}}<br>
                <strong>Description:</strong> {{$content->description}}<br>
                <strong>Password:</strong> {{$content->password}}<br>
                <strong>Starting point:</strong> {{$content->starting_point}}<br>
            </p>
            <div style="background-image: url('data:image/gif;base64,{{$img_base_64}}');
                        background-repeat: no-repeat;
                        background-size: contain;
                        background-position: center;
                        width: 100%;
                        height: 300px;
                        border: 1px solid #ccc;
                        border-radius: 5px;
                        margin-bottom: 10px;">
            </div>
            <div style="display: flex; justify-content: space-between">
                <a href="{{ route('reset.name', ['token' => $token]) }}" class="btn">Reset name</a>
                <a href="{{ route('reset.description', ['token' => $token]) }}" class="btn">Reset description</a>
                <a href="{{ route('reset.password', ['token' => $token]) }}" class="btn">Reset password</a>
                <a href="{{ route('reset.picture', ['token' => $token]) }}" class="btn">Reset picture</a>
                <a href="{{ route('close.ticket', ['token' => $token]) }}" class="btn">Done</a>
            </div>
        </div>
    @endif

    @if($type == 3)
        <div style="background-color: #f9f9f9; padding: 10px; border-radius: 5px;">
            <p style="margin-bottom: 10px;">
                <strong>ID:</strong> {{$content->id}}<br>
                <strong>Nickname:</strong> {{$content->nickname}}<br>
            </p>
            <div style="background-image: url('data:image/gif;base64,{{$img_base_64}}');
                        background-repeat: no-repeat;
                        background-size: cover;
                        background-position: center;
                        width: 100%;
                        height: 300px;
                        border: 1px solid #ccc;
                        border-radius: 5px;
                        margin-bottom: 10px;">
            </div>
            <div style="display: flex; justify-content: space-between">
                <a href="{{ route('reset.nickname', ['token' => $token]) }}" class="btn">Reset nickname</a>
                <a href="{{ route('close.ticket', ['token' => $token]) }}" class="btn">Done</a>
            </div>
        </div>
    @endif

    <h5 style="margin-top: 20px;">Reported by user with ID: {{$userId}}</h5>
</div>
