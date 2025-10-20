import { BellAlertIcon } from "@heroicons/react/24/solid";

/**
 * A function component that renders a logo with a bell alert icon.
 *
 * @param {string} [siteTitle='WP Buko'] - The title of the site.
 * @returns {JSX.Element} - A JSX element representing the logo.
 */
export default function Logo({ siteTitle = 'WP Buko' }) {
    return (
        <a href="/"> <BellAlertIcon />{siteTitle}</a>
    )
}