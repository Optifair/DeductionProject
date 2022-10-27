import { styled, alpha } from '@mui/material/styles';
import AppBar from '@mui/material/AppBar';
import Box from '@mui/material/Box';
import Toolbar from '@mui/material/Toolbar';
import IconButton from '@mui/material/IconButton';
import Typography from '@mui/material/Typography';
import InputBase from '@mui/material/InputBase';
import NotificationsIcon from '@mui/icons-material/Notifications';
import SearchIcon from '@mui/icons-material/Search';
import { AccountCircle } from '@mui/icons-material';
import { Badge } from '@mui/material';
import Link from '@mui/material/Link'



const Search = styled( 'div' )( ( { theme } ) => ( {
  position: 'relative',
  borderRadius: theme.shape.borderRadius,
  backgroundColor: alpha( theme.palette.common.white, 0.15 ),
  '&:hover': {
    backgroundColor: alpha( theme.palette.common.white, 0.25 ),
  },
  marginLeft: 0,
  width: '100%',
  [ theme.breakpoints.up( 'sm' ) ]: {
    marginLeft: theme.spacing( 1 ),
    width: 'auto',
  },
} ) );

const SearchIconWrapper = styled( 'div' )( ( { theme } ) => ( {
  padding: theme.spacing( 0, 2 ),
  height: '100%',
  position: 'absolute',
  pointerEvents: 'none',
  display: 'flex',
  alignItems: 'center',
  justifyContent: 'center',
} ) );

const StyledInputBase = styled( InputBase )( ( { theme } ) => ( {
  color: 'inherit',
  '& .MuiInputBase-input': {
    padding: theme.spacing( 1, 1, 1, 0 ),
    paddingLeft: `calc(1em + ${ theme.spacing( 4 ) })`,
    transition: theme.transitions.create( 'width' ),
    width: '100%',
    [ theme.breakpoints.up( 'sm' ) ]: {
      width: '12ch',
      '&:focus': {
        width: '20ch',
      },
    },
  },
} ) );

export default function Header ()
{
  return (
    <Box sx={ { flexGrow: 1 } }>
      <AppBar position="fixed">
        <Toolbar>
          <Typography
            variant="h6"
            noWrap
            component="div"
            sx={ { flexGrow: 1, display: { xs: 'none', sm: 'block' } } }
          >
            <Link href='/' underline='none' variant="inherit" color={ 'whitesmoke' }>
              Home
            </Link>
          </Typography>
          <Search>
            <SearchIconWrapper>
              <SearchIcon />
            </SearchIconWrapper>
            <StyledInputBase
              placeholder="Search…"
              inputProps={ { 'aria-label': 'search' } }
            />
          </Search>
          <Box sx={ { display: { xs: 'none', md: 'flex' } } }>
            <IconButton
              size="large"
              aria-label="show 17 new notifications"
              color="inherit"
            >
              <Badge badgeContent={ 17 } color="error">
                <NotificationsIcon fontSize='large' />
              </Badge>
            </IconButton>
            <IconButton
              size="large"
              edge="end"
              aria-haspopup="true"
              color="inherit"
            >
              <AccountCircle fontSize='large' />
            </IconButton>
          </Box>
        </Toolbar>
      </AppBar>
    </Box>
  );
}