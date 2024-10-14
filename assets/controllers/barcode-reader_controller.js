import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    onInput(event) {
        let value = event.target.value;
        let mappedValue = this.mapCharacters(value);

        if (mappedValue !== value) {
            event.target.value = mappedValue;
        }
    }

    mapCharacters(value) {
        const mapping = {
            '+': '1',
            'ě': '2',
            'š': '3',
            'č': '4',
            'ř': '5',
            'ž': '6',
            'ý': '7',
            'á': '8',
            'í': '9',
            'é': '0'
        };

        return value.split('').map(c => {
            const lowerC = c.toLowerCase();
            return mapping[lowerC] || c;
        }).join('');
    }
}
