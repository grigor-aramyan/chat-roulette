
const SET_CURRENT_USER = 'SET_CURRENT_USER';
const SET_CURRENT_USER_MODE = 'SET_CURRENT_USER_MODE';

module.exports = {
    
    state: () => ({
        currentUser: null
    }),

    mutations: {
        SET_CURRENT_USER (state, userData) {
            state.currentUser = userData;
        },
        SET_CURRENT_USER_MODE (state, mode) {
            if (state.currentUser) {
                state.currentUser.mode = mode;
            }
        }
    },

    actions: {
        setCurrentUser ({ commit }, userData) {
            commit(SET_CURRENT_USER, userData);
        },
        setCurrentUserMode({ commit }, mode) {
            commit(SET_CURRENT_USER_MODE, mode);
        }
    }
}