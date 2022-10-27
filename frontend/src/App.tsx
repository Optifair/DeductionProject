import React from 'react';
import Header from './Components/Header';
import
{
  BrowserRouter as Router,
  Routes,
  Route
} from 'react-router-dom';
import PostsListPage from './Components/PostsListPage';
import { Box } from '@mui/material';
import { ThemeProvider, createTheme } from '@mui/material/styles';
import CssBaseline from '@mui/material/CssBaseline';

const darkTheme = createTheme( {
  palette: {
    mode: 'dark',
  },
} );

export default function App ()
{
  const routes = [
    { path: '/', element: <PostsListPage /> },
  ];

  return (
    <ThemeProvider theme={ darkTheme }>
      <CssBaseline />
      <Box>
        <Router>
          <Header />
          <Routes>
            { routes.map( ( route, key ) => (
              <Route path={ route.path } element={ route.element } key={ key } />
            ) ) }
          </Routes>
        </Router>
      </Box>
    </ThemeProvider>

  );
}