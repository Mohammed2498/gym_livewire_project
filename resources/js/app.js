import './bootstrap';
import 'toastr/build/toastr.css';
import toastr from 'toastr';
import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();
window.toastr = toastr;

