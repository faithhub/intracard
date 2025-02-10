import {
  LayoutDashboardIcon,
  HomeDollarIcon,
  CreditCardIcon,
  WalletIcon,
  ChartLineIcon,
  MessageCircleIcon
} from 'vue-tabler-icons';

const state = {
  user: JSON.parse(localStorage.getItem("user")) || null, // Load user data from localStorage
};

// Extract the account goal dynamically from the user data
const accountGoal = state.user?.account_goal; // Default to 'rent' if no goal is set

const sidebarItem = [
  { header: 'Account' },
  {
    title: 'Dashboard',
    icon: LayoutDashboardIcon,
    BgColor: 'primary',
    to: '/',
  },
  {
    title: accountGoal === 'rent' ? 'Rent Details' : 'Mortgage Details', // Dynamic title based on account goal
    icon: HomeDollarIcon,
    BgColor: 'primary',
    to: '/account',
  },
  {
    title: 'My Cards',
    icon: CreditCardIcon,
    BgColor: 'primary',
    to: '/cards',
  },
  {
    title: 'Wallet',
    icon: WalletIcon,
    BgColor: 'primary',
    to: '/wallet',
  },
  {
    title: 'Credit Advisory',
    icon: ChartLineIcon,
    BgColor: 'primary',
    to: '/credit-advisory',
  },
  {
    title: 'Help & support',
    icon: MessageCircleIcon,
    BgColor: 'primary',
    to: '/support',
  },
];

export default sidebarItem;
