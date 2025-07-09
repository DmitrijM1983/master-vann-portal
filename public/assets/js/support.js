function openSupportModalWindow() {
    document.getElementById('supportModal').style.display = "block";
}

function closeSupportModalWindow() {
    document.getElementById('supportModal').style.display = "none";
}

function printFileName() {
    const input = document.getElementById('support-photo');
    document.getElementById('support-file-name').textContent = input.files[0] ? input.files[0].name : 'Файл не выбран';
}

function openUserSupportsWindow() {
    document.getElementById('user-supports-window').style.display = "block";
}

function closeUserSupportsWindow() {
    document.getElementById('user-supports-window').style.display = "none";
}
