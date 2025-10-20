import Toggle from './components/Toggle';
import { useBlockProps } from '@wordpress/block-editor';
import './style.scss';

export default function Edit() {
	return (
		<div {...useBlockProps()}>
			<Toggle />
		</div>
	);
}
