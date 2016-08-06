
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

