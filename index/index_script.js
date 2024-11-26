document.getElementById('show-register').addEventListener('click', function() {
    document.getElementById('login-form-container').classList.add('hidden');
    document.getElementById('register-form-container').classList.remove('hidden');
});
document.getElementById('back-to-login').addEventListener('click', function() {
    document.getElementById('login-form-container').classList.remove('hidden');
    document.getElementById('register-form-container').classList.add('hidden');
});
function validateEULA() {
    const eulaCheckbox = document.getElementById('preference');
    if (!eulaCheckbox.checked) {
        alert('You must agree to the EULA before registering.');
        return false;
    }
    return true;
}