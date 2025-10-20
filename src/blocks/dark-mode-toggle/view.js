
import { createRoot } from '@wordpress/element';
import Toggle from '../../components/Toggle';

document.addEventListener("DOMContentLoaded", () => {
    const container = document.querySelector('.dark-mode-toggle-wrapper');
    const root = createRoot(container);
    root.render(<Toggle />);
});
