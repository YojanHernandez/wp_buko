import { useState, useEffect } from '@wordpress/element';
import { SunIcon, MoonIcon } from "@heroicons/react/24/solid";

/**
 * A toggle button for dark mode.
 *
 * It gets the initial dark mode state from the local storage or the
 * browser's preferred color scheme. It then toggles the dark
 * mode class on the document element and updates the local
 * storage when the button is clicked.
 *
 * @return {JSX.Element} The toggle button.
 */
export default function Toggle() {
    const getInitialDarkMode = () => {
        const localTheme = localStorage.getItem("theme");
        const browserTheme = matchMedia("(prefers-color-scheme: dark)").matches;
        return localTheme === "dark" || (!localTheme && browserTheme);
    };

    const [darkMode, setDarkMode] = useState(getInitialDarkMode);

    useEffect(() => {
        document.documentElement.classList.toggle("dark", darkMode);
    }, [darkMode]);

    const toggleDarkMode = () => {
        let newMode = !darkMode;
        setDarkMode(!darkMode);
        document.documentElement.classList.toggle("dark", !darkMode);
        localStorage.setItem("theme", newMode ? "dark" : "light");
    };

    return (
        <button
            aria-label="Toggle dark mode"
            onClick={toggleDarkMode}
            className="dark-mode-toggle"
        >
            {darkMode ? (<SunIcon className="dark-mode-toggle__icon" />)
                : (<MoonIcon className="dark-mode-toggle__icon" />)
            }
        </button>
    );
}
