import './bootstrap';

Echo.channel('notifications')
    .listen('UserSessionChangedEvent', (e) => {
        const notificationElement = document.getElementById('notification');
        notificationElement.innerText = e.message;

        notificationElement.classList.remove('invisible');
        notificationElement.classList.remove('success');
        notificationElement.classList.remove('danger');

        notificationElement.classList.add('alert-' + e.type);
    });
