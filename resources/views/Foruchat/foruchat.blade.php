@extends('default')


@section('pTitle', ucfirst($director->categorie) . ' - forum firme')
@section('pDesc', ucfirst($director->categorie) . ' - forum firme ' . ucfirst($director->categorie) )
@section('pURL',APP_PUBLIC_URL)






@section('container')


    <div class="container">
        <div class="row">

            <div class="col-xs-12">

                <style>
                    img.uav {
                        border-radius: 12px;
                        padding: 3px;
                        width: 24px;
                        height: 24px;
                    }

                    @media (min-width: 1200px) {
                        #chatWindow {
                          margin: 0 0 20px 0;
                          padding: 0 0 0 15px;
                        }
                        #usersWindow {
                            margin: 0 0 20px 0;
                            padding: 0 15px 0 0;
                        }
                        #wellConv {
                            height: 400px;
                        }
                        #wellUsers {
                            height: 400px;
                        }
                    }


                    img.avatar48 {
                        border-radius: 24px;
                        padding: 3px;
                        width: 48px;
                        height: 48px;
                    }

                    /* Foruchat page */
                    table.messages {
                        width: 100%;
                        margin 2px 2px 0 5px;
                        font-size: 85%;
                        line-height: 200%;
                    }



                    span.msgopt {
                        font-size: 80%;
                        color: dodgerblue;
                    }

                    td.avatar {
                        vertical-align: top;
                        padding-right: 5px;
                    }

                    td.msgusr > strong {
                        font-weight: normal;
                        color: dodgerblue;
                        font-size: 110%;
                        margin: 0 2px 0 2px;
                        display: inline-block;
                        transform : scale(1,1.3);
                        -webkit-transform:scale(1,1.3); /* Safari and Chrome */
                        -moz-transform:scale(1,1.3); /* Firefox */
                        -ms-transform:scale(1,1.3); /* IE 9+ */
                        -o-transform:scale(1,1.3); /* Opera */
                    }

                </style>


                {{-- Containerul de categorii pe prima pagina --}}
                <div class="row">

                    <div id="chatWindow" class="col-lg-9 col-md-12">

                        <div id="wellConv" class="well" style="padding: 2px 2px 0 2px; margin: 0 0 5px 0;">
                            <div style="height: 100%; display: flex; align-items: flex-end;">
                                <div style="height: 100%; width: 100%; overflow-y: scroll;" id="divMsg">
                                    <table id="messages" class="messages">
                                        @if(!empty($messages))
                                            @foreach($messages as $m)
                                                <tr class="msg">
                                                    <td class="avatar">
                                                        @if(!empty($m['avatar']))
                                                            <img src="{{APP_AVATARS_URL}}/{{$m['avatar']}}" class="avatar48">
                                                        @endif
                                                     </td>
                                                    <td class="msgusr">
                                                       &lt;<strong>{{$m['username']}}</strong>&gt;
                                                         :
                                                       <span class="msgtxt">{{$m['message']}}</span>
                                                        <br>
                                                       <span class="msgopt">
                                                           Reply
                                                       <span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    </table>
                                </div>
                            </div>
                        </div>

                        @if(Auth::check())
                            <form id="messageForm">
                                <div class="input-group">
                                    <input type="text" class="form-control" id="msg" placeholder="Mesajul tau..." autocomplete="off">
                                    <span class="input-group-btn">
                                          <button class="btn btn-success" type="button"
                                                  onclick="$('#messageForm').submit()">Trimite!
                                          </button>
                                     </span>
                                </div>
                            </form>
                        @else
                            <div class="alert alert-warning" role="alert"><i class="fa fa-warning"></i> Pentru acces la
                                discutii trebuie sa va
                                <a href="{{route('index_path')}}/auth/facebook" rel="nofollow"><i
                                            class="fa fa-facebook"></i> autentificati</a></div>
                        @endif

                    </div>

                    <div class="col-lg-3 col-md-12" id="usersWindow">


                        <div id="wellUsers" class="well" style="padding: 2px 2px 0 2px; margin: 0 0 5px 0;">
                            <div style="height: 100%; display: flex; align-items: flex-end;">
                                <div style="height: 100%; width: 100%; overflow-y: scroll; font-size: 70%;" id="divUsers">

                                    @if(!empty($online_users))

                                        @foreach($online_users as $u)
                                            <span class="userChat" id="usrl{{$u->id}}"><img src="{{APP_AVATARS_URL}}/{{$u->avatar}}" class="uav"> &lt;<strong>{{$u->username}}</strong>&gt;<br></span>
                                        @endforeach

                                    @endif

                               </div>
                           </div>
                        </div>



                    </div>


                </div>

            </div>

        </div>

    </div>

@endsection


@push('scripts')
<script>
    var CHANNEL = '{{$channel}}';
</script>

<script>
    $("#messageForm").unbind('submit').submit( function(e) { e.preventDefault();
        socket.emit( 'channel', {
            user_id: AUTH_USER_ID,
            pkey:    AUTH_PRIVATE_KEY,
            message: $("#msg").val(),
            channel: CHANNEL
        });
        $("#msg").val('');
        Scrl('#divMsg');
        return false;
    });


    socket.on( CHANNEL, function( data ) {
        var avatar;
        if ( data.avatar ) {
             avatar = '<img src="' + APP_AVATAR_URL + '/' + data.avatar + '"> ';
        }
        $("#messages").append('<div><span class="userChat">' + avatar + '&lt;<strong>' + data.username + ' </strong>&gt;</span> : <span class="message">' + data.message + '</span></div>');
        Scrl('#divMsg');
    });

    Scrl('#divMsg');


    setInterval(function() {
        socket.emit( 'online:users', {
           user_id  : AUTH_USER_ID,
           pkey     : AUTH_PRIVATE_KEY,
           channel  : CHANNEL
        });
    },10000);

    socket.on( CHANNEL + ":online", function( data ) {

        // alert(JSON.stringify(data));

        var avatar;
        if (data.user.avatar) {
            avatar = '<img src="' + APP_AVATAR_URL + '/' + data.user.avatar + '" class="uav"> ';
        }

        if ( $("#usrl" + data.user.id).length == 0)  {
            $("#divUsers").append('<span class="userChat" id="usrl' + data.user.id + '">' + avatar + '&lt;<strong>' + data.user.username + '</strong>&gt;<br></span>');
        }


    });


    socket.on( 'disc_user_{{$channel}}', function  (data) {
          $("#usrl" + data.user_id).remove();
    });

</script>


{{--<script src="{{APP_PUBLIC_URL}}/js/chat.js"></script>--}}

@endpush











