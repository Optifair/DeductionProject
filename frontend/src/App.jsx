import Main from './components/Main';
import SinglePost from './components/blogComponents/SinglePost';
import NotFound from './components/blogComponents/NotFound';
import Profile from './components/Pages/Profile';
import { lightTheme, darkTheme, GlobalStyles } from './theme';
import { ThemeProvider } from 'styled-components';
import {
  ChakraProvider, Container, Switch, HStack,
  Modal, ModalOverlay, ModalContent, ModalHeader, ModalFooter,
  ModalBody, ModalCloseButton, Flex, Box,
  useDisclosure, Button, FormControl, Input, ListItem, UnorderedList, Spacer, Stack, Avatar
} from '@chakra-ui/react'
import React, { useState, useEffect } from 'react';
import { Routes, Route, Link, BrowserRouter } from "react-router-dom";
import { MoonIcon, SunIcon, Search2Icon } from '@chakra-ui/icons';
import './App.css';

export default function App() {
  const [theme, setTheme] = useState("dark");
  const [isSwitchOn, setIsSwitchOn] = useState(false);
  const [searchTerm, setSearchTerm] = useState([]);
  const [searchResultitems, setSearchResultItems] = useState([]);
  const { isOpen, onOpen, onClose } = useDisclosure();
  const initialRef = React.useRef();


  const changeThemeSwitch = () => {
    let newValue = !isSwitchOn;
    setIsSwitchOn(newValue);

    !newValue ? setTheme('dark') : setTheme('light');
  }

  const fetchSearchResults = async (searchTerm) => {
    const res = await fetch(
      `http://DeductionProject/api/searchResult?keyword=${searchTerm}`,
      {
        method: "GET",
        headers: {
          "Access-Control-Allow-Origin": "*",
          "Content-Type": "application/json"
        }
      }
    );

    return await res.json();
  }

  function slug(string) {
    return string.toLowerCase()
      .replace(/ /g, '-')
      .replace(/[^\w-]+/g, '');
  }

  useEffect(() => {
    const getUsersInput = setTimeout(() => {
      fetchSearchResults(searchTerm).then((items) => {
        setSearchResultItems(items.posts);
      });
    }, 100)

    return () => clearTimeout(getUsersInput)
  }, [searchTerm])


  return (
    <ChakraProvider>
      <ThemeProvider theme={theme === 'light' ? lightTheme : darkTheme}>
        <BrowserRouter>
          <GlobalStyles />
          <Box bg={theme === "light" ? '#333' : '#fff'}
            borderBottom={theme === "light" ? 'solid 1px #333' : 'solid 1px #fff'}
            color={theme === "light" ? '#fff' : '#333'} px={4}
          >
            <Flex h={16} alignItems={'center'} justifyContent={'space-between'}>
              <HStack spacing={16} alignItems={'left'}>
                <HStack as={'nav'}
                  spacing={6}
                  display={{ base: 'none', md: 'flex' }}>
                  <Link to="/">
                    Main Page
                  </Link>

                </HStack>
              </HStack>
              <Search2Icon onClick={onOpen}></Search2Icon>
              <Flex alignItems={'center'}>
                <Spacer></Spacer>
                <Stack direction={'row'} spacing={7}>
                  <Switch onChange={changeThemeSwitch}>
                    {isSwitchOn ? (<MoonIcon mr="5" />) : (<SunIcon mr="5" />)}
                  </Switch>
                </Stack>
                <Link to="/contact">
                  <Avatar src='https://bit.ly/broken-link'>
                  </Avatar>
                </Link>
              </Flex>
            </Flex>
          </Box>
          <Modal
            initialFocusRef={initialRef}
            isCentered
            onClose={onClose}
            isOpen={isOpen}
            motionPreset='slideInBottom'
            bg='blue'
          >
            <ModalOverlay bg='none'
              backdropFilter='auto'
              backdropInvert='80%'
              backdropBlur='2px' />
            <ModalContent>
              <ModalHeader color={'#333'}>Type Keyword to search</ModalHeader>
              <ModalCloseButton />
              <ModalBody pb={6}>
                <FormControl mt={4}>
                  <Input placeholder=''
                    ref={initialRef}
                    color={'#333'}
                    onChange={(e) => setSearchTerm(e.target.value)} />
                </FormControl>
                <br />
                {searchResultitems &&
                  <UnorderedList>
                    {searchResultitems.map(function (item) {
                      return (<Link to={slug(item.title)} key={item.id} state={item.id}><ListItem key={item.id}>{item.title}</ListItem></Link>)
                    })}
                  </UnorderedList>
                }
              </ModalBody>
              <ModalFooter>
                <Button onClick={onClose}>Cancel</Button>
              </ModalFooter>
            </ModalContent>
          </Modal>


          <div className='App'>
            <Container maxW="1200px" marginTop={'50px'}>
              <Routes>
                <Route path="/" element={<Main />} />
                <Route path="/contact" element={<Profile />} />
                <Route path=":slug" element={<SinglePost />} />
                <Route path="/404" element={<NotFound />} />
              </Routes>
            </Container>
          </div>
        </BrowserRouter>
      </ThemeProvider>
    </ChakraProvider>
  );
}
