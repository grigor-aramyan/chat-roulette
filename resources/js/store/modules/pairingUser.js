
const SET_PAIRING_USER = 'SET_PAIRING_USER';
const SET_PAIRING_USER_ANSWERS = 'SET_PAIRING_USER_ANSWERS';
const REMOVE_PAIRING_USER_ANSWERS = 'REMOVE_PAIRING_USER_ANSWERS';
const REMOVE_PAIRING_USER = 'REMOVE_PAIRING_USER';
const ADD_NEW_CHAT_MESSAGE = 'ADD_NEW_CHAT_MESSAGE';
const REMOVE_CHAT_MESSAGES = 'REMOVE_CHAT_MESSAGES';

module.exports = {
    
    state: () => ({
        pairingUser: null,
        pairingUserAnswers: null,
        chatMessages: null
    }),

    mutations: {
        SET_PAIRING_USER (state, userData) {
            state.pairingUser = userData;
        },
        SET_PAIRING_USER_ANSWERS (state, answers) {
            state.pairingUserAnswers = answers;
        },
        REMOVE_PAIRING_USER_ANSWERS (state) {
            state.pairingUserAnswers = null;
        },
        REMOVE_PAIRING_USER (state) {
            state.pairingUser = null;
        },
        ADD_NEW_CHAT_MESSAGE (state, newMessage) {
            if (state.chatMessages) {
                state.chatMessages.push(newMessage);
            } else {
                state.chatMessages = [];
                state.chatMessages.push(newMessage);
            }
        },
        REMOVE_CHAT_MESSAGES (state) {
            state.chatMessages = null;
        }
    },

    actions: {
        setPairingUser ({ commit }, userData) {
            commit(SET_PAIRING_USER, userData);
        },
        setPairingUserAnswers ({ commit }, answers) {
            commit(SET_PAIRING_USER_ANSWERS, answers);
        },
        removePairingUserAnswers ({ commit }) {
            commit(REMOVE_PAIRING_USER_ANSWERS);
        },
        removePairingUser ({ commit }) {
            commit(REMOVE_PAIRING_USER);
        },
        addNewChatMessage({ commit }, newMessage) {
            commit(ADD_NEW_CHAT_MESSAGE, newMessage);
        },
        removeChatMessages({ commit }) {
            commit(REMOVE_CHAT_MESSAGES);
        }
    }
}