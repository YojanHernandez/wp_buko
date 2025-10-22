import { createRoot } from '@wordpress/element';
import ScheduleComponent from '../../components/Schedule';

document.addEventListener("DOMContentLoaded", () => {
    const containers = document.querySelectorAll('.wp-buko-schedule__wrapper');

    for (const container of containers) {
        const root = createRoot(container);
        const attributes = container.dataset.schedule;
        root.render(<ScheduleComponent data={attributes} />);
    }
});
