import React, { useEffect, useState } from 'react';
import { Stack } from '@mui/system';
import PostCard from './PostCard';
import { json } from 'stream/consumers';
import { useParams } from 'react-router-dom';


export default function SearchPage ()
{
    const params = useParams();
    const keyWord = params.keyWord;
    const [ posts, setPosts ] = useState( [] );

    async function fetchPosts ()
    {
        var res = await fetch(
            `http://DeductionProject/api/searchPostsByKey?key=` + keyWord
        );

        return await res.json();
    }

    useEffect( () =>
    {
        fetchPosts().then( ( posts ) =>
        {
            setPosts( posts.posts );
        } );
    } )

    return (
        <Stack spacing={ 3 } alignItems={ 'center' } paddingBottom={ '30px' }>
            <Stack spacing={ 4 } className="s" alignItems={ 'center' } paddingTop={ '90px' }>
                { posts.map( function ( { id, title, content, user_id, image } )
                {
                    return <PostCard key={ id } id={ id } title={ title } content={ content } userId={ user_id }
                        image={ image } />
                } ) }
            </Stack>
        </Stack>
    );

}
