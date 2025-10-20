import { __ } from '@wordpress/i18n';
import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import { TextControl } from '@wordpress/components';
import Logo from '../../components/Logo';

export default function Edit({ attributes, setAttributes }) {
	const { title } = attributes;

	return (
		<div {...useBlockProps()} >
			<InspectorControls>
				<TextControl
					label={__('Title', 'wp-buko')}
					value={title}
					placeholder={__('Site Title', 'wp-buko')}
					onChange={(newTitle) => {
						setAttributes({ title: newTitle });
					}}
				/>
			</InspectorControls>
			<div className=' wp-buko-logo'>
				<Logo siteTitle={title} />
			</div>
		</div >
	);
}
