import { useState } from 'react';
import ArrowUpwardIcon from '@mui/icons-material/ArrowUpward';
import { IconButton } from '@mui/material';


const ScrollButton = () =>
{

    const [ visible, setVisible ] = useState( false )

    const toggleVisible = () =>
    {
        const scrolled = document.documentElement.scrollTop;
        if ( scrolled > 300 )
        {
            setVisible( true )
        }
        else if ( scrolled <= 300 )
        {
            setVisible( false )
        }
    };

    const scrollToTop = () =>
    {
        window.scrollTo( {
            top: 0,
            behavior: 'smooth'
        } );
    };

    window.addEventListener( 'scroll', toggleVisible );

    return (
        <IconButton onClick={ scrollToTop } style={ { position: 'fixed', right: '10%', bottom: '10%' } }>
            <ArrowUpwardIcon
                style={ { display: visible ? 'inline' : 'none', fontSize: 'xxx-large' } } />
        </IconButton>
    );
}

export default ScrollButton;
