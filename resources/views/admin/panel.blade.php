@extends(backpack_view('blank'))

@section('content')
    <div class="container">
        <h1>Welcome to Notifications Page</h1>
        <button onclick="startFCM()" class="btn btn-danger btn-flat">Allow notification
        </button>
        <h2>Notifications</h2>
        <ol>
            @foreach ($notifications as $notification)
                <li><b>{{ $notification->name }}</b> - {{ $notification->created_at->diffForHumans() }}</li>
            @endforeach
        </ol>
    </div>

    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <script src="https://www.gstatic.com/firebasejs/8.3.2/firebase.js"></script>
    <script>
        var firebaseConfig = {
            apiKey: "AIzaSyDCZHZDFqWk36CMmrRxqNGdcqlnRGhtgt4",
            authDomain: "metalowy-52916.firebaseapp.com",
            projectId: "metalowy-52916",
            storageBucket: "metalowy-52916.appspot.com",
            messagingSenderId: "270548902371",
            appId: "1:270548902371:web:8c55737222cab6b88b80d6"
        };
        firebase.initializeApp(firebaseConfig);
        const messaging = firebase.messaging();

        function startFCM() {

            messaging
                .requestPermission()
                .then(function() {
                    return messaging.getToken()
                })
                .then(function(response) {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.ajax({
                        url: '{{ route('store.token') }}',
                        type: 'POST',
                        data: {
                            token: response
                        },
                        dataType: 'JSON',
                        success: function(response) {
                            // alert('Token stored.');
                        },
                        error: function(error) {
                            alert(error);
                        },
                    });
                }).catch(function(error) {
                    alert(error);
                });
        }
        messaging.onMessage(function(payload) {
            const title = payload.notification.title;
            const options = {
                body: payload.notification.body,
                icon: payload.notification.icon,
            };
            new Notification(title, options);
        });
    </script>
@endsection
