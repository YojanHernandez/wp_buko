
import { registerBlockType } from '@wordpress/blocks';
import { useBlockProps, R } from '@wordpress/block-editor';
import metadata from './block.json'

const Edit = () => <p {...useBlockProps()}> Hello world from the editor</p>

const Save = () => <p {...useBlockProps.save()}> Hello world from the browser</p>

registerBlockType(metadata.name, {
    edit: Edit,
    save: Save
})