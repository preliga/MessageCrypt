$(function() {

    var messages;
    var messageSound = new Audio("/sounds/alertMessage.mp3");

    var invitations;
    var invitationSound = new Audio("/sounds/alertInvitation.mp3");

    var conn = new WebSocket('ws://localhost:8080');

    conn.onopen = function(e) {
        var msg = '{"authToken":"'+authToken+'"}';

        conn.send(msg);
    };

    conn.onmessage = function(e) {

        try {
            var data = JSON.parse(e.data);

            if(messages !== data['messages']) {
                messages = data['messages'];
                messagesArray = JSON.parse(messages);
                updateMessages(messagesArray);
                updateMessagesAlert(messagesArray);
            }

            if(invitations !== data['invitations']) {
                invitations = data['invitations'];
                invitationsArray = JSON.parse(invitations);
                updateInvitationsAlert(invitationsArray);
            }

        } catch(ex){
            console.log(e.data);
        }

    };

    function updateMessages(messages) {
        var html = "<li>";

        for(var i=0; i < messages.length && i < 3; i++) {
            var user = messages[i];
            var isRead = user.read == 0? "alertColor": "";
            var avatar = user.avatar !== null? "/uploads/avatars/" + user.avatar: "/images/empty.png";

            html +=
                "<a class='list-group-item " +  isRead + "' href='/user/messages/showAll/"+user.id+"'>"+
                "<img class='littleImg' src='" + avatar +"' style='width: 100px;'>"+
                "<span style='font-size: 13px;'> " + user.name + " " + user.lastname + "</span>"+
                "</a>";
        }

        html += "</li>"

        $('.messagesList').html(html);
        console.log(html);
    }

    function updateMessagesAlert(messages) {
        var notRead = 0;
        for(var i=0; i < messages.length; i++) {
            if(messages[i].read == 0) {
                notRead++;
            }
        }

        if(notRead > 0) {
            html = "<div class='mainMenuAlert'><span>" + notRead + " message" + (notRead > 0?"s":"") + "</span></div>";
            $('.messagesLabel').addClass('alertColor');

            if(notRead > notReadBase) {
                notReadBase = notRead;
                messageSound.play();
            } else {
                notReadBase = notRead;
            }
        } else {
            html = "";
            $('.messagesLabel').removeClass('alertColor');
        }

        $('.messagesAlert').html(html);
    }

    function updateInvitationsAlert(invitations) {
        console.log(invitations);
        var countInvitations = invitations.length;

        if(countInvitations > 0) {
            html = "<div class='mainMenuAlert'><span>" + countInvitations + " invitation" + (countInvitations > 0?"s":"") + "</span></div>";
            $('.invitationsLabel').addClass('alertColor');

            if(countInvitations > countInvitationsBase) {
                countInvitationsBase = countInvitations;
                invitationSound.play();
            } else {
                countInvitationsBase = countInvitations;
            }
        } else {
            html = "";
            $('.invitationsLabel').removeClass('alertColor');
        }

        $('.invitationsAlert').html(html);
    }
});
