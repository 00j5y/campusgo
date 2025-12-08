const tabLogin = document.getElementById('tab-login');
const tabRegister = document.getElementById('tab-register');
const loginView = document.getElementById('login-view');
const registerView = document.getElementById('register-view');

const activeClasses = ['bg-white', 'shadow-sm', 'text-noir'];
const inactiveClasses = ['text-gray-noir', 'hover:text-noir'];

function switchTab(view) {
    if (view === 'login') {
        loginView.classList.remove('hidden');
        registerView.classList.add('hidden');
        tabLogin.classList.add(...activeClasses);
        tabLogin.classList.remove(...inactiveClasses);
        tabRegister.classList.remove(...activeClasses);
        tabRegister.classList.add(...inactiveClasses);
    } else {
        registerView.classList.remove('hidden');
        loginView.classList.add('hidden');
        tabRegister.classList.add(...activeClasses);
        tabRegister.classList.remove(...inactiveClasses);
        tabLogin.classList.remove(...activeClasses);
        tabLogin.classList.add(...inactiveClasses);
    }
}

tabLogin.addEventListener('click', () => switchTab('login'));
tabRegister.addEventListener('click', () => switchTab('register'));