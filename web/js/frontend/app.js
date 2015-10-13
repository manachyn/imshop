export default class App {
    constructor(id, name) {
        this.id = id;
        this.name = name;
    }
    static init() {
        console.log('INIT');
    }
}
