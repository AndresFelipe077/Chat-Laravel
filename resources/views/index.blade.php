<!DOCTYPE html>
<html lang="en">
<head>
  <title>Chat Laravel</title>
  <link rel="icon" href="{{asset('img/felpil.png')}}"/>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- JavaScript -->
  <script src="https://js.pusher.com/7.2/pusher.min.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
  <!-- End JavaScript -->

  <!-- CSS -->
  <link rel="stylesheet" href="/style.css">
  <!-- End CSS -->

</head>

<body>
<div class="chat">

  <!-- Header -->
  <div class="top">
    <div class="d-flex">
      <img src="https://avatars.githubusercontent.com/u/102833427?s=400&u=c764b7d5310b92ded57785118e8d319506904ae1&v=4" height="150px" width="150px" alt="Avatar">
      <div class="overflow-hidden ms-3">
        <a class="text-dark mb-0 h6 d-block text-truncate" href="/page-chat">
          Ross Edlin
        </a>
        <small class="text-muted">
          <i class="mdi mdi-checkbox-blank-circle text-success on-off align-text-bottom"></i> Online
        </small>
      </div>
    </div>
  </div>
  <!-- End Header -->

  <!-- Chat -->
  <div class="messages">
    @include('left', ['message' => "Hey! What's up!  👋"])
    @include('left', ['message' => "Ask a friend to open this link and you can chat with them!"])
  </div>
  <!-- End Chat -->

  <!-- Footer -->
  <div class="bottom">
    <form>
      <input type="text" id="message" name="message" placeholder="Enter message..." autocomplete="off">
      <button type="submit"></button>
    </form>
  </div>
  <!-- End Footer -->

</div>
</body>

<script>
  const pusher = new Pusher('{{config('broadcasting.connections.pusher.key')}}', {cluster: 'us2'});
  const channel = pusher.subscribe('public');

  //Receive messages
  channel.bind('chat', function (data) {
    $.post("/message", {
      _token: '{{csrf_token()}}',
      message: data.message,
    })
     .done(function (res) {
       $(".messages > .message").last().after(res);
       $(document).scrollTop($(document).height());
     });
  });

  //Send messages
  $("form").submit(function (event) {
    event.preventDefault();

    $.ajax({
      url: "/",
      method: 'POST',
      headers: {
        'X-Socket-Id': pusher.connection.socket_id
      },
      data: {
        _token: '{{csrf_token()}}',
        message: $("form #message").val(),
      }
    }).done(function (res) {
      $(".messages > .message").last().after(res);
      $("form #message").val('');
      $(document).scrollTop($(document).height());
    });
  });

</script>
</html>
