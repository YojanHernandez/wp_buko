
import { createRoot } from '@wordpress/element';
import Logo from '../../components/Logo';

document.addEventListener("DOMContentLoaded", () => {
    const containers = document.querySelectorAll('.wp-buko-logo');

    for (const container of containers) {
        const root = createRoot(container);
        const siteTitle = container.dataset.siteTitle;
        root.render(<Logo siteTitle={siteTitle} />);
    }
});
