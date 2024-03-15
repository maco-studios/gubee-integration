import { createApp } from 'vue'
import './assets/css/style.css'
import App from './vue/components/App.vue'
import Validation from './vue/components/Validation.vue'

if (!window.gubeeIntegration) {
    window.gubeeIntegration = {}
}

document.addEventListener('DOMContentLoaded', function () {
    var doc = document.querySelector('body.gubee-install-index .page-wrapper')
    if (!doc) {
        return
    }

    // replace element with a empty div
    doc.innerHTML = '<div id="app"></div>'
    createApp(App, {
        props: {
            configUrl: window.gubeeIntegration.settingsUrl,
            docsUrls: window.gubeeIntegration.docsUrls,
            storeUrl: "https://gubee.com.br/",
        }
    }).mount('#app')
})


document.addEventListener('DOMContentLoaded', () => {
    var errors = document.querySelector('body.catalog-product-edit #gubee_validation span').innerText;
    if (errors) {
        console.log("Before", errors)
        errors = JSON.parse(errors);
        console.log("After", errors)
        document.querySelector('#gubee_validation').setAttribute('data-errors', errors.errors.length);
        document.querySelector('#gubee_validation span').innerText = '';
    }
    var divPlaceholder = document.createElement('div');
    document.body.appendChild(divPlaceholder);
    createApp(
        Validation,
        {
            errors: errors.errors,
            payload: errors.product
        }
    ).mount(divPlaceholder);

});
