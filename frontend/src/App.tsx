import Header from './Components/Header';
import {BrowserRouter as Router, Route, Routes} from 'react-router-dom';
import PostsListPage from './Components/PostsListPage';
import {Box} from '@mui/material';
import {createTheme, ThemeProvider} from '@mui/material/styles';
import CssBaseline from '@mui/material/CssBaseline';
import AuthPage from './Components/AuthPage';
import NotFoundPage from './Components/NotFoundPage';
import SearchPage from './Components/SearchPage';
import RegistrationPage from "./Components/RegistrationPage";
import ProfilePage from "./Components/ProfilePage";
import MarksPage from "./Components/MarksPage";
import PasswordChangePage from "./Components/PasswordChangePage";
import ToastBar from "./Components/ToastBar";

const darkTheme = createTheme({
    palette: {
        mode: 'dark',
    },
});

export default function App() {
    const routes = [
        {path: '/', element: <PostsListPage/>},
        {path: '/search/:keyWord', element: <SearchPage/>},
        {path: '/auth', element: <AuthPage/>},
        {path: '/registration', element: <RegistrationPage/>},
        {path: '/profile', element: <ProfilePage/>},
        {path: '/marks', element: <MarksPage/>},
        {path: '/changePassword/:key', element: <PasswordChangePage/>},
        {path: '/404', element: <NotFoundPage/>},
    ];

    return (
        <ThemeProvider theme={darkTheme}>
            <CssBaseline/>
            <Box>
                <Router>
                    <Header/>
                    <Routes>
                        {routes.map((route, key) => (
                            <Route path={route.path} element={route.element} key={key}/>
                        ))}
                    </Routes>
                    <ToastBar/>
                </Router>
            </Box>
        </ThemeProvider>

    );
}