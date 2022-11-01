import React, { useEffect, useState } from 'react';
import { Stack } from '@mui/system';
import PostCard from './PostCard';
import { Box, Button } from '@mui/material';
import ScrollTopButton from './ScrollTopButton';

export default function PostsListPage ()
{
  const [ postsTotal, setPostsTotal ] = useState( undefined );
  const [ posts, setPosts ] = useState( [] );
  const [ pageSize, setPageSize ] = useState<number>( 5 );


  const offset = 0;

  async function fetchPosts ( pageSize: any, offset: any )
  {
    const res = await fetch(
      `http://DeductionProject/api/posts?limit=${ pageSize }&offset=${ offset }`
    );

    return await res.json();
  }

  useEffect( () =>
  {
    fetchPosts( pageSize, offset ).then( ( posts ) =>
    {
      setPostsTotal( posts.count );
      setPosts( posts.posts );
    } );
  }, [ pageSize, offset ] )

  async function loadNewPosts ()
  {
    setPageSize( ( pageSize ) => pageSize + 5 );
  }


  return (
    <Stack spacing={ 3 } alignItems={ 'center' } paddingBottom={ '30px' }>
      <Stack spacing={ 4 } className="s" alignItems={ 'center' } paddingTop={ '90px' }>
        { posts.map( function ( { id, title, content, user_id, image } )
        {
          return <PostCard key={ id } id={ id } title={ title } content={ content } userId={ user_id }
            image={ image } />
        } ) }
      </Stack>
      <Button onClick={ loadNewPosts } variant="outlined" style={ { border: '1px solid ghostwhite', color: 'ghostwhite' } }>Show more</Button>
      <ScrollTopButton />
    </Stack>
  );

}
