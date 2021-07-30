export const state = () => ({
    drawer: true,
    mode: 'web'
});

export const mutations = {
    toggle(state) {
        state.drawer = !state.drawer;
    },
    changeMode(state, mode) {
        state.drawer = mode === 'web';
        state.mode = mode;
        console.log(state.mode);
    }
};

export const getters = {
    getDrawerState(state) {
        return state.drawer;
    },
    getModeState(state) {
        return state.mode;
    }
};
