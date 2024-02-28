import { createApp } from 'vue'
import './css/style.css';
import Validation from './components/Validation.vue'

document.addEventListener('DOMContentLoaded', () => {
    var errors = document.querySelector('#gubee_validation span').innerText;
    if (errors) {
        errors = JSON.parse(errors);
        document.querySelector('#gubee_validation').setAttribute('data-errors', errors.length);
        document.querySelector('#gubee_validation span').innerText = '';
    }
    var divPlaceholder = document.createElement('div');
    document.body.appendChild(divPlaceholder);
    createApp(
        Validation,
        {
            errors: errors
        }
    ).mount(divPlaceholder);

});
