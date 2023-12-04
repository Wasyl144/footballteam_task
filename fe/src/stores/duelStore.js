import { defineStore } from 'pinia';

export const useDuelStore = defineStore('duel', {
  state: () => ({
    round: 0,
    yourPoints: 0,
    opponentPoints: 0,
    status: 'active',
    isCardSelected: false,
    selectedCard: null,
    disabledCards: [],
    cards: [],
  }),
  actions: {
    setIsCardSelected(status) {
      this.isCardSelected = status;
    },
    setSelectedCard(card) {
      this.selectedCard = card;
    },
    disableCard(id) {
      if (!this.disabledCards.includes(id)) {
        this.disabledCards.push(id);
      }
    },
    isCardDisabled(id) {
      return this.disabledCards.includes(id);
    }
  },
});