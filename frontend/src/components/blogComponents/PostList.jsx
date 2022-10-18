import { Box, Center, Flex, Avatar } from '@chakra-ui/react'
import React from 'react';
import { Link } from "react-router-dom";


export default function PostList({ id, title, content, userId, image }) {

    function slug(string) {
        return string.toLowerCase()
            .replace(/ /g, '-')
            .replace(/[^\w-]+/g, '');
    }


    return (
        <Box maxW='container.md' borderWidth='2px' borderRadius='lg'
            overflow='hidden' m={15} margin='auto' alignContent={'center'}>
            <Center>
                <Link to={slug(title)} state={id}>
                    <Box p={6}>
                        <Box display='flex' alignItems='baseline'>
                            <Avatar src='https://bit.ly/broken-link'>
                            </Avatar>
                            <Box slug={title}
                                mt='1'
                                fontWeight='bold'
                                as='h4'
                                fontSize='large'
                                lineHeight='tight'>
                                {title}
                            </Box>
                        </Box>
                        <hr />
                        <Box slug={content}
                            mt='1'
                            as='h4'
                            lineHeight='tight'>
                            {content}
                        </Box>
                        <br />
                        <Flex>
                            <img src={image} alt={title} style={{
                                margin: 'auto',
                            }} />
                        </Flex>
                    </Box>

                </Link>
            </Center>
        </Box>
    )
}
