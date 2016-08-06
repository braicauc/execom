@extends('default')


@section('pTitle', ucfirst($director->categorie) . ' - forum firme')
@section('pDesc', ucfirst($director->categorie) . ' - forum firme ' . ucfirst($director->categorie) )
@section('pURL',APP_PUBLIC_URL)






@section('container')

    <style>
        dt.pnt {
            cursor: pointer;
        }

        dt.pnt:hover {
            text-decoration: underline;
            color: darkgreen;
        }
    </style>

    <div class="container">
        <div class="row">

            <div class="col-xs-12">


                {{-- Containerul de categorii pe prima pagina --}}
                <div class="row">

                    <div class="col-lg-8 col-md-12">

                        <div class="well" style="height: 400px; padding: 2px 2px 0 2px; margin: 0 0 5px 0;">
                            <div style="height: 100%; display: flex; align-items: flex-end;">
                                <div style="height: 100%; width: 100%; overflow-y: scroll;" id="divMsg">
                                    <dl class="dl-horizontal" id="messages">

                                    </dl>
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

                    <div class="col-lg-4 col-md-12">



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
        $("#messages").append('<dt><strong>' + data.username + '</strong></dt><dd>' + data.message + '</dd>');
        Scrl('#divMsg');
    });
</script>


{{--<script src="{{APP_PUBLIC_URL}}/js/chat.js"></script>--}}

@endpush











