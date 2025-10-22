
import { TextControl, PanelBody } from '@wordpress/components';
import { InspectorControls, useBlockProps, RichText } from '@wordpress/block-editor';
import "react-day-picker/style.css";
import Schedule from '../../components/Schedule';

export default function Edit({ attributes, setAttributes }) {
	const { title,
		dateLabel,
		timeLabel,
		namePlaceholder,
		emailPlaceholder,
		buttonText,
		successMessage,
		errorMessage,
		noSlotsMessage
	} = attributes;
	const blockProps = useBlockProps();
	return (
		<div {...blockProps} className="wp-buko-schedule">
			<InspectorControls>
				<PanelBody title="Configuración">
					<TextControl
						label="Title"
						value={title}
						onChange={(value) => setAttributes({ title: value })}
					/>
					<TextControl
						label="Date Label"
						value={dateLabel}
						onChange={(value) => setAttributes({ dateLabel: value })}
					/>
					<TextControl
						label="Time Label"
						value={timeLabel}
						onChange={(value) => setAttributes({ timeLabel: value })}
					/>
					<TextControl
						label="Name Placeholder"
						value={namePlaceholder}
						onChange={(value) => setAttributes({ namePlaceholder: value })}
					/>
					<TextControl
						label="Email Placeholder"
						value={emailPlaceholder}
						onChange={(value) => setAttributes({ emailPlaceholder: value })}
					/>
					<TextControl
						label="Button Text"
						value={buttonText}
						onChange={(value) => setAttributes({ buttonText: value })}
					/>
					<TextControl
						label="Success Message"
						value={successMessage}
						onChange={(value) => setAttributes({ successMessage: value })}
					/>
					<TextControl
						label="Error Message"
						value={errorMessage}
						onChange={(value) => setAttributes({ errorMessage: value })}
					/>
					<TextControl
						label="No Slots Message"
						value={noSlotsMessage}
						onChange={(value) => setAttributes({ noSlotsMessage: value })}
					/>
				</PanelBody>
			</InspectorControls>

			<h2 className="wp-buko-schedule__title"><RichText value={title} onChange={(value) => setAttributes({ title: value })} /></h2>
			<div className="wp-buko-schedule__wrapper">
				<Schedule data={JSON.stringify(attributes)} />
			</div>
		</div>
	);
}
