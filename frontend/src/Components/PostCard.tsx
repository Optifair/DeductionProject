import React from 'react';
import SubscribeIcon from '@mui/icons-material/Bookmarks';
import ExpandMoreIcon from '@mui/icons-material/ExpandMore';
import { Avatar, Box, Card, CardActions, CardContent, CardHeader, CardMedia, Collapse, Divider, IconButton, IconButtonProps, styled, Typography } from '@mui/material';
import { red } from '@mui/material/colors';

type Params = {
    id: string;
    title: string;
    content: string;
    userId: string;
    image: string;
}


interface ExpandMoreProps extends IconButtonProps
{
    expand: boolean;
}

const ExpandMore = styled( ( props: ExpandMoreProps ) =>
{
    const { expand, ...other } = props;
    return <IconButton { ...other } />;
} )( ( { theme, expand } ) => ( {
    transform: !expand ? 'rotate(0deg)' : 'rotate(180deg)',
    marginLeft: 'auto',
    transition: theme.transitions.create( 'transform', {
        duration: theme.transitions.duration.shortest,
    } ),
} ) );

export default function PostCard ( { id, title, content, userId, image }: Params )
{

    const [ expanded, setExpanded ] = React.useState( false );

    const handleExpandClick = () =>
    {
        setExpanded( !expanded );
    };

    return (
        <Card sx={ { maxWidth: '60%', minWidth: 500, borderRadius: '25px' } }>
            <Box padding={ '10px' }>
                <CardHeader sx={ { paddingBottom: '0px', fontSize: 'large' } }
                    avatar={
                        <Avatar sx={ { bgcolor: red[ 500 ], height: 60, width: 60 } } src='https://bit.ly/broken-link' >
                        </Avatar>
                    }
                    action={
                        <IconButton aria-label="settings">
                            <SubscribeIcon fontSize='large' />
                        </IconButton>
                    }
                    title={
                        <Typography variant="h5">
                            { userId }
                        </Typography>
                    }
                />
                <CardContent sx={ { paddingTop: '5px' } }>
                    <Typography textAlign={ 'center' } variant="h5" paddingBottom={ '10px' }>
                        { title }
                    </Typography>
                    <Typography variant="body1">
                        { content }
                    </Typography>
                </CardContent>
                <Box margin={ 'auto' } maxWidth="70%" paddingBottom={ '20px' }>
                    <CardMedia
                        component="img"
                        image={ image }
                    />
                </Box>
                <Divider />
                <CardContent>
                    <Typography paragraph>
                        Main Comment
                    </Typography>
                </CardContent>
                <CardActions disableSpacing>
                    <Box alignItems={ 'center' } margin={ 'auto' }>
                        <ExpandMore
                            expand={ expanded }
                            onClick={ handleExpandClick }
                            aria-expanded={ expanded }
                        >
                            <ExpandMoreIcon />
                        </ExpandMore>
                    </Box>
                </CardActions>
                <Collapse in={ expanded } timeout="auto" unmountOnExit>
                    <CardContent>
                        <Typography paragraph>
                            Other Comments Comments Comments
                        </Typography>
                    </CardContent>
                </Collapse>
            </Box>
        </Card >
    );
}






