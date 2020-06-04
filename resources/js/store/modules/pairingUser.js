const SET_PAIRING_USER = 'SET_PAIRING_USER';

module.exports = {
    
    state: () => ({
        pairingUser: null
    }),

    mutations: {
        SET_PAIRING_USER (state, userData) {
            state.pairingUser = userData;
        }
    },

    actions: {
        setPairingUser ({ commit }, userData) {
            commit(SET_PAIRING_USER, userData);
        }
    }
}